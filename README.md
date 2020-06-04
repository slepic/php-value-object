# php-value-object
Simple PHP Value Objects and Enums

# Requirements

* PHP >=7.4

# Installation

```
composer require slepic/value-object
```

# Introduction

Value objects are guards of validity. Once a value object is constructed, it contains valid data.
And as long as that object lives, we don't have to validate it again.

This packages provides a uniform way of writing value objects.

```
function log(float $x): float
{
  if ($x <= 0.0) {
    throw new \InvalidArgumentException();
  }

  // your implementation
}
```

Are you tired of these checks in your code. Value objects to the rescue!

```
function log (PositiveFloat $value): float
{
  $x = $value->toFloat();
  // $x is definitely a positive float

  // your implementation
}
```

or if you dont't want to delegate the responsibility for creating the value object to the outsider.

```
function log(float $x): float
{
  new PositiveFloat($x);
  // now $x is sure positive, otherwise an exception has been thrown

  // your implementation
}
```

But wait, there is more. Since all the value objects have uniform exceptions,
we can easily implement nonuniform collections, like data transfer objects.

And guess what? They are included in this package.

## Validation, Error Reporting

Lets say we have a user registration endpoint and we model the request structure like this:
```
class UserRegistration
{
  public string $userName;
  public string $email;
  public string $password;
  public array $groupIds;
}
```

Easy, right? But further in our code we check that username has only letters
and is at most some characters long to fit into our db column.
Also email has to be a valid address. Password has some limitations too.
And group ids are exactly 10 char long hexadecimal strings.

So we validate those restrictions before we create the model instance and
anyone who uses that instance is then left wondering if it really contains valid data.

So we put our validations into the class's constructor to be sure.

We can get much better than this.

This package provides a single exception:
ViolationExceptionInterface

this exception has a single method getViolations() which returns an array of violations.
A violation is an instance of ViolationInterface
which represents a machine readable error code and human readable error message.
The error code is represented by the violation class itself.
This allows for inheritance between error codes and also errors codes can carry additional data.

Every value objects is free to throw ViolationException with as many violations as it likes.
Usualy scalar value objects throw just one, while collections may throw more.
Although, nothing prevents you from throwing multiple violations from a scalar value object.

Collection violations also provide a way to describe the expected type through TypeExpectationInterface
which is basically an abstract over ReflectionClass, but also works for builtin types.

The validation of value objects is therefore "report all errors" rather than "report first error"
which is more suitable for APIs, although the usage of value objects is sure not restricted to those use cases.


```
final class UserName extends BoundedMbLengthString
{
  protected static function minLength(): int {return 5;}
  protected static function minLength(): int {return 64;}

  public function __construct(string $value) {
    StringPatternViolation::check('/^[a-zA-Z0-9]+$/', $value);
    parent::__construct($value);
  }
}

final class UserPassword extends SomeBaseString
{
  // implement some restrictoins in constructor
}

final class GroupId extends BoundedRawLengthString implements
  FromStringConstructableInterface
{
  protected static function minLength(): int {return 10;}
  protected static function minLength(): int {return 10;}

  public function __construct(string $value) {
    StringPatternViolation::check('/^[a-f0-9]+$/', $value);
    parent::__construct($value);
  }

  public statuc function fromString(string $value): self
  {
    // notice if we didnt finalize the class
    // we should at least finalize the constructor
    return new self($value);
  }
}

final class GroupIds extends ArrayList
{
  // ArrayList uses the return typehint for constructor validation
  public function current(): GroupId
  {
    return parent::current();
  }
}

class UserRegistration extends DataTransferObject
{
  public UserName $userName;
  public EmailAddress $email;
  public UserPassword $password;
  public GroupIds $groupIds;
}

// and voila

try {
  $registration = new UserRegistration([
    'userName' => new UserName($request->name),
    'email' => new EmailAddress($request->email),
    'password' => new UserPassword($request->password),
    'groupIds' => new GroupIds($request->groups),
  ]);
} catch (ViolationExceptionInterface $e) {
  return $this->createErrorResponse($e->getViolations());
}

$result = $this->registerUser($registration);
return $this->createSuccessResponse($result);
```   

It is up to you, if and how you present the violations to he client. But it is not restricted to request validations.
There are probably use cases, when you only care if exception is thrown or not and basically treat it as an InvalidArgumentException.

Maybe in future the violations collections will have a default json serialization methods, but currently it is up to you.

## Upcasting

Now we finally have a registration object that is sure valid and we can immediately tell.
But wait won't this throw TypeErrors if someone sends me a integer as a user name, or array as email.
Yes it will, but we have solution for this as well.
Just have the value objects implements one of our "upcasting" interfaces.
I've actually included one in the example above where GroupIds collection is already relying on it
to convert array of strings to array of GroupId instances.

Currently these interfaces are not implemented by default, because there would be no way to turn it off.
And also when implementing these interfaces, you should finalize your constructors
and we don't want to finalize them in the base classes
(it is more likely that in future we will provide a set of base classes with sealed constructors).

When we implement relevant upcasting interfaces on all the value objects we can end up doing just this:
```
$registration = new UserRegistration([
    'userName' => $request->name,
    'email' => $request->email,
    'password' => $request->password,
    'groupIds' => $request->groups,
]);
```

Now we no longer are worried about TypeErrors, because the value objects engine
will solve this for you, if improper types are passed a ViolationExceptionInterface
will be thrown with TypeViolation violation for the mismatching types.

The code has became a bit longer and if you honor PSR-4 you've probably created quite a bit new files.
But the increase in explicitness about your validity expectations is worth it, IMO.

## Downcasting

The package also supports downcasting of value objects to primitve types.

```
class MyDto extends DataTransferObject
{
  public string $primiteValue;
}

new MyDto([
  'primitiveValue' => new StringValue('xy')
]);
```

Downcasting is automatically invoked by collections when they encounter value object where they expect a primitive type.
A value object must implement the appropriate downcasting interface, 
ie. ToStringConvertibleInterface. An object with a __toString method will not be downcasted
unless also implements the ToStringConvertibleInterface

And unlike upcasting, downcasting interface are implemented by the base value objects, but
only for the primitive type that is actually used as the underlying representation.

Ie. IntegerValue has a __toString method, but does not implement ToStringConvertibleInterface.
On other hand it implements ToIntConvertibleInterface because it uses int as the underlying type. 

## Overview

Often we need to wrap primitive values and enforce some kind of limitation, like a limit on a string length,
allow only subset of all characters in a string, or limit a maximum value of a number.

Simple implementations of scalar objects with these common restrictions can be found in the package. 

### Strings
 * `Slepic\ValueObject\Strings\StringValue`
   * a string value object without restrictions
   * violation: `StringViolation`
 * `Slepic\ValueObject\Strings\MaxRawLengthString`
   * a string value object with max length (using strlen)
   * children need to implement `protected static function maxLength(): int`
   * violation: `StringTooLong`
 * `Slepic\ValueObject\Strings\MinRawLengthString`
   * a string value object with min length (using strlen)
   * children need to implement `protected static function minLength(): int`
   * violation: `StringTooShort`
 * `Slepic\ValueObject\Strings\BoundedRawLengthString`
   * string value object with both min and max length (using strlen)
   * children need to implement both `protected static function minLength(): int` and `protected static function maxLength(): int`
   * violation: `StringLengthOutOfBounds`
 * `Slepic\ValueObject\Strings\MaxMbLengthString`
   * a string value object with max length (using mb_strlen)
   * children need to implement `protected static function maxLength(): int`
   * violation: `StringTooLong`
 * `Slepic\ValueObject\Strings\MinMbLengthString`
   * a string value object with min length (using mb_strlen)
   * children need to implement `protected static function minLength(): int`
   * violation: `StringTooShort`
 * `Slepic\ValueObject\Strings\BoundedMbLengthString`
   * string value object with both min and max length (using mb_strlen)
   * children need to implement both `protected static function minLength(): int` and `protected static function maxLength(): int`
   * violation: `StringLengthOutOfBounds`
 * `Slepic\ValueObject\Strings\RegexTemplateString`
   * a string value object which checks the value to match a regex pattern
   * children need to implement `protected static function pattern(): string`
   * violation: `StringPatternViolation`
   
### Integers

* see Slepic\ValueObject\Integers namespace

### Floats

* see Slepic\ValueObject\Floats namespace

### Enums

* see Slepic\ValueObject\Enums namespace

We consider several axis for enums
* strong vs. weak
    * strong enums
      * strong enums exist as singleton instances
      * strict comparision using `===` and `!==` is possible
    * weak enums
      * new instances can be created to represent the same value
    * must compare the underlying value to tell if the instances are the same
* value types
    * all allowed values of an enum must be of same type
    * we distinguish between string enums and int enums
    * float enums can be supported in future, but we dont have a use case for it now
* the way the set of allowed values is defined
    * class's constants values
    * class's constants keys
    * class's named constructors
    * custom way, driven by the enum class.
    
Each aspect has cons and pros.
Currently only strong string enums are implemented.

### Collections

The package supports 3 main types of collections

* see Slepic\ValueObject\Collections namespace

#### DataTransferObject
* expects keys of the array to match its public property name and the values must match the corresponding property type
* the property types are denoted by their typehints.
* violations:
  * InvalidPropertyValue - if a known property has errors
  * MissingRequiredProperty - if a property without default value is not provided
  * UnknownProperty - if input contains property that does not exist on the DTO
    * this can be turned off in the children by overriding the class's protected constant IGNORE_UNKNOWN_PROPERTIES
   
```
class MyDto extends DataTransferObject
{
  public int $intProperty;
  public string $stringProperty;
}

new MyDto([
  'intProperty' => 10,
   'stringProperty' => 'text',
]); // ok
new MyDto([]); //ViolationsException with 2 MissingRequiredProperty violations
```
   
#### ArrayList
* expects iterable with zero based index keys and all values matching the same type
* the value type is denoted by the return type of the `current()` method.
* violations:
  * InvalidListItem - when an item violates the expected type
  * TypeViolation - when indexes are not zero based
   
```
class MyList extends ArrayList
{
   public function current(): int
   {
      return parent::current();
   }
}

new MyList([1,2,3]); // ok
new MyList(['1', 2, 3]); // ViolationException with a InvalidListItem violation

```
   
#### ArrayMap
* expects associative array with string keys and values matching the same type
* the value type is denoted by the return tpe of the `current()` method.
* violations:
    * InvalidPropertyValue - if a property value is invalid.
    
```
class MyMap extend ArrayMap
{
  public function current(): float
  {
     return parent::current();
  }
}

new MyMap(['a' => 1.0, 'b' => 2.0, 'c' => 3.5]); // ok
new MyMap(['a' => 1, 'b' => 2.0, 'c' => 3.5]); //ViolationException with InvalidPropertyValue
```

### Standards

Additionaly we provide a set of standard value objects for common things, like email, etc.
But this sections is currently not ready and in future this may probably be in a separate package.

* see Slepic\ValueObject\Standard namespace
