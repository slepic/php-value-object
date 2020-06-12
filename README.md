# php-value-object
PHP Value Objects

# Requirements

* PHP >=7.4

# Installation

```
composer require slepic/value-object
```

# Introduction

This library aims to provide unification of commonalities of all value objects.
Additionally, it provides some features that take advantage of the unified environment:
 * unified language to describe violations can be integrated with validation systems
 * common base value objects for enums, collections and scalar types with restrictions
 * interfaces to unify conversions from and to primitive types, or even between objects
 * automatic construction of composite value objects using their own class definition

## What is a value object?

Value objects are guards of validity. Once a value object is constructed, it contains valid data.
And as long as that object lives, we don't have to validate it again.

```
final class FullName
{
  public string $firstName;
  public string $surname;

  public function __construct(string $firstName, string $surname)
  {
    if ($firstName === '') {
      throw \InvalidArgumentException(''First name cannot be empty.');
    }

    if ($surname === '') {
      throw new \InvalidArgumentException('Surname cannot be empty.');
    }

    $this->firstName = $firstName;
    $this->surname = $surname;
  }
}
```

Note: For simplicity, I am exposing everything in the examples as public properties which allows modifications.
They should really be private with public getters.

## What commonalities do value objects have?

* They are (or at least should be) immutable
* They cannot be constructed into invalid state
* Attempt to construct them with invalid data leads to exception
* They can be constructed from primitive data types
* They can be converted to primitive data types

## What this library can unify?

* invalid state is reported as an exception - we can unify what kind of exception is thrown
* convertability from/to primitives - we can unify how value objects expose these abilities
* common value objects - we can provide a set of common value objects for many applications to reuse

## What this library cannot unify?

* immutability - this is always going to be responsibility of the implementor of a concrete value object
* valid state after construction - we cannot enforce this either

## How can we take advantage of the unifications?

### Unified errors

Since value objects need valid state, they have to check it.
And this inherently means that they are doing validations and they have to do it themselves.
If we just let value objects throw \InvalidArgumentException and instead we do validations (with client error reporting)
beforehand, we are basically doing the validation twice.
Once to feed the client with reasonable explanation where he screwed up
and once in the value object to make sure it's not constructed from bullocks.
And if we are lazy, we omit one or the other (or both in worst case).

Omitting validation outside value objects means that our value objects may throw and we end up with 500 Internal Server Error.
Omitting validation inside value objects means that we will never be truly sure that they are valid.
Omitting them both is just disaster.
Having both is redundant and may lead to de-synchronization between the two.

If our value objects do the validations (which they should anyway) 
and offer a unified way to describe their expectations and eventual violations of said expectations,
they effectively force you to validate your data:
* once
* in whichever point in code you find appropriate
* using the logic of your value objects 

Unification of how value objects represent violations allows applications to incorporate value objects
errors into their input validation process.

However, the way this incorporation is done for specific application is outside the scope of the library.

This library attempts to unify the error exception as `ViolationExceptionInterface` with a `getViolations(): array<ViolationInterface>` method.
A default implementation `ViolationException` is also provided by the library.

`ViolationInterface` is a marker interface for violations and each violation class name represents an error code.
With possibility to carry additional information exposed as properties/getters.
This also allows for error code inheritance and avoids error code conflicts between vendors.

Having an array of violations also gives is the option to report multiple violations at the same time.   

```
final class FullName
{
  public string $firstName;
  public string $surname;

  public function __construct(string $firstName, string $surname)
  {
    $violations = []
    if ($firstName === '') {
      $violations[] = new Violation(''First name cannot be empty.');
    }

    if ($surname === '') {
      $violations[] = new Violation('Surname cannot be empty.');
    }

    if (\count($violations) > 0) {
      throw new ViolationException($violations);
    }

    $this->firstName = $firstName;
    $this->surname = $surname;
  }
}

try {
  $slimShady = new FullName('Slim', 'Shady');
} catch (ViolationExceptionInterface $e) {
  return $this->processViolations($e->getViolations());
}
```

A set of implementations is provided by this library, including some violations that describe nested errors of collections.

### Unified conversions

Often value objects provide named constructors that allow to construct them from a primitive.
And it isn't also uncommon that value objects can convert themselves to a primitive type.

This library provides a set of interfaces that define how conversion from and to primitive types should look like.

In the example below, they are the `FromStringConstructableInterface` and `ToStringConvertibleInterface`,
defining the `public static function fromString(string $value): self` and `public function __toString(): string` methods respectively.

This gives as unified environment.
It is sure easier to work with if all value objects that can be constructed from a single string value
have the same named constructor for this.
And, well, in case of conversion to string, that is already covered by PHP's magic method `__toString()`,
but we also offer interfaces for int, float, etc. that go about the same names like `ToIntConvertibleInterface` and so on... 

```
final class FullName implements FromStringConstructableInterface, ToStringConvertibleInterface
{
  // ...

  public static function fromString(string $fullName): FullName
  {
    $parts = \explode(' ', $fullName, 2);
    if (\count($parts) !== 2) {
      throw \InvalidArgumentException('Full name must contain first name, a space and the surname.');
    }
    return new FullName($parts[0], $parts[1]);
  }

  public function __toString(): string
  {
    return $this->firstName . ' ' . $this->surname;
  }
}

$slimShady = FullName::fromString('Slim Shady');
echo (string) $slimShady;
```

And it allows to automatically construct composite value objects using their own class definition.
See collections section.

### Common value objects

Now, wait a minute! Both first name and surname check the same thing.
Let's get rid of that duplication.

```
final class StringIsEmpty extends Violation
{
  public function __construct(string $message = '')
  {
    parent::__construct($message ?: 'Value cannot be empty');
  }
}

class NonEmptyString
{
  private string $value;

  public function __construct(string $value)
  {
    if ($value === '') {
      throw ViolationException::for(new StringIsEmpty());
    }
    $this->value = $value;
  }

  public function __toString(): string
  {
    return $this->value;
  }
}

final class FullName
{
  public NonEmptyString $firstName;
  public NonEmptyString $surname;

  public function __construct(NonEmptyString $firstName, NonEmptyString $surname)
  {
    $this->firstName = $firstName;
    $this->surname = $surname;
  }
}
```

We have moved the responsibility for checking the value emptiness to the `NonEmptyString` class.
However we have lost the ability to be specific about which property it is that is empty.

That is however responsibility of the caller of the constructor,
because he is now creating those NonEmptyString value objects.

Let's see such a caller in the form of a factory that creates the object from primitive strings.
It now manages the error codes and messages and overrides the defaults, while using the same codes (violation classes).
```
function createFullName(string $firstName, string $surname): FullName
{
    $violations = [];
    
    try {
      $f = new NonEmptyString($firstName);
    } catch (ViolationExceptionInterface $e) {
      $violations[] = new StringIsEmpty('First name cannot be empty.');
    }
    
    try {
      $s = new NonEmptyString($surname);
    } catch (ViolationExceptionInterface $e) {
      $violations[] = new StringIsEmpty('Surname cannot be empty.');
    }
    
    if (\count($violations) > 0) {
      throw new ViolationException($violations);
    }
    
    return new FullName($f, $s);
}
```

But we have also added a new type that is now a guarantee of non empty string.

And you know, if you don't care that much for all the violations, you do just this:
```
function createFullName(string $firstName, string $surname): FullName
{
    return new FullName(
        new NonEmptyString($firstName),
        new NonEmptyString($surname)
    );
}
```

Anyway, we can now avoid some checks on other places.

```
function extractFirstChar(NonEmptyString $text): string
{
  // if we accepted the primitive `string $text` here, we should check its emptiness
  // and throw an exception otherwise. Now we don't have to :)
  return \substr((string) $text, 0, 1);
}

echo extractFirstChar($fullName->firstName) . '.' . extractFirstChar($fullName->surname) . '.';
```

This effectively forces the caller to validate the input at some point,
while leaving the function to care only about its logic.

This library provides a set of base value objects that encapsulate some common restrictions we have on our primitive data types.

Note: ViolationInterface implementations should only describe the error, what it is that was violated.
If consumers want to know everything that could have been violated, they have to reach for the value object type itself. 
 
## Base Value Objects

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

Note: If you sometimes need to construct the object not from array, but directly from separate variables,
consider not extending DataTransferObject, implement your object, with own constructor and
use `FromArrayConstructor` helper class to simplify the creation from array. 
   
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

### FromArrayConstructor
This is not a value object, it is a static helper class which simplifies construction of objects
from associative array of named parameters for their class constructor.

```
class MyValueObject
{
  private string $x;
  private int $y;

  public function __construct(string $x, int $y)
  {
    $this->x = $x;
    $this->y = $y;
  }

  public static function fromArray(array $data): self
  {
    return FromArrayConstructor::constructFromArray(static::class, $data);
  }

  public function with(array $data): self
  {
    return FromArrayConstructor::combineWithArray($this, $data);
  }

  public functin toArray(): array
  {
    return FromArrayConstructor::extractConstructorArguments($this);
  }
}

$vo = MyValueObject::fromArray([
  'x' => 'test',
  'y' => 10,
]);

$vo2 = $vo->with(['y' => $vo->y + 1]);
```

This helper also supports upcasting and downcasting. See upcasting/downcasting sections.

Constructor parameters must have a default value to become optional in the input array.
The parameters also must have a typehint.

By default the method reports any unexpected properties of the input through `UnknownProperty` violation.
This can be turned off by passing true as the 3rd parameter `$ignoreExtraProperties`.

Basically, this method throws the same violations as DataTransferObject.

The `combineWithArray` method expects that non-instance properties exists with the same name as each constructor parameter,
that is not passed as the `$data` argument to the `combineWithArray` method.
These properties must have a compatible type with the respective constructor parameter.
Calling the `combineWithArray` method on objects that don't obey this rule, will result in a LogicException
and no violations will be reported. 

The `extractConstructorArguments` method expects non-instance properties exist with the same name as each constructor parameter,
These properties must have a compatible type with the respective constructor parameter.
Calling the `extractConstructorArguments` method on objects that don't obey this rule, will result in a LogicException
and no violations will be reported. 

Note: writing these value objects will become even easier with PHP8's constructor promotion.
If you only need your object constructed from array and the constructor itself seems like a burden,
you should consider DataTransferObject instead.

### Standards

Additionaly we provide a set of standard value objects for common things, like email, etc.
But this sections is currently not ready and in future this may probably be in a separate package.

* see Slepic\ValueObject\Standard namespace

### Upcasting

Whenever a collection expects a value object type and it receives a primitive type,
it will look for the appropriate upcasting interface on the target value object class.
If it exists, it will automatically construct the value object using the interface.

```
final class MyDto extends DataTransferObject
{
  public StringValue $string;
}

new MyDto([
  'string' => 'value',
]);
```

Existing upcasting interfaces are:
* FromIntConstructableInterface
* FromFloatConstructableInterface
* FromStringConstructableInterface
* FromArrayConstructableInterface
* FromObjectConstructableInterface
* FromBoolConstructableInterface

### Downcasting

Whenever a collection expects a primitive type and it receives an object,
it will look for the appropriate downcasting interface on the value.
If it exists it will be used to obtain the primitive value.

```
fina class MyDto extends DataTransferObject
{
  public string $string;
}

new MyDto([
  'string' => new StringValue('value'),
]);
```

Existing downcasting interface are:
* ToIntConvertibleInterface
* ToFloatConvertibleInterface
* ToStringConvertibleInterface
* ToArrayConvertibleInterface
* ToBoolConvertibleInterface


# FAQ

### Is this only usable for validation

No. That's only a side effect that it allows to enhance and integrate with your validation system.

### What other use cases are there.

This is basically for any use case where you would use a value object.
It just helps you write them in unified way and simplifies some of its concerns.

### How about contextual validation? Some field must be an email if another field has a specific value?

That is up to the constructor of the value object. Such a rule cannot be attributed to either of the fields alone.
It's absolutely fine to define your own violation class for that and eventually reuse it in multiple value objects.

### How about nested validation rules? A client (user/api consumer etc) is sending data to your app and it gets back some errors? How do I communicate back "hey, addresses[5].state is not a valid state"?

The communication of invalid state to the client is under control of your application.
You can take advantage on unified violations environment, but you still have to be aware of
what kinds of violations your value objects throw and represent each of them accordingly.
Although violations are represented as classes, they really are just simple error codes
with additional features leveraging the PHP class system.
The violations from Collections namespace should make it easy enough to create nested violations
for nested value objects. See the code of the collections to see how they are used.
And you can also check their tests to see how we check for what happened in the nested violations tree.

### How about custom validation messages?

Validation messages provided by ViolationInterface::getMessage() are not meant to be communicated to the client directly.
They represent an immediately readable explanation of the violation for a developer.
But if the violations are to be communicated to a general user, the messages should be generated
within your application's validation system, based on the error codes and eventually other violation properties.

You can use the default messages probably only on API where devs are the only ones who is going to read them.

Chances are though, that in future the ViolationInterface::getMessage() method will be removed entirely.

Some component that simplify this task of integrating the violations into validation systems may be created in future.
At this point this is out of the scope of the library, and it would probably become a separate package anyway.

### How about validation that requires dependencies (eg: a database connection)?

It is of course possible to throw the unified ViolationExceptionInterface from any factory you want.
But this cannot happen within the automatic upcasting, since it happens through static named constructors. 
But there is no problem passing an already created value object to an automatically constructed value object from this package.
And that makes it irrelevant whether the passed-in value object needed database to be created or not. 
