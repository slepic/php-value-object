.phony: install test cs-check cs-fix psalm

DOCKER=docker-compose run --rm php
CS=--standard=ruleset.xml src tests

INSTALL=composer install --no-interaction
TEST=vendor/bin/phpunit --colors=always tests
CSCHECK=vendor/bin/phpcs $(CS)
CSFIX=vendor/bin/phpcbf $(CS)
PSALM=vendor/bin/psalm

install:
	$(DOCKER) $(INSTALL)

githooks:
	.githooks/init.sh

test:
	$(DOCKER) $(TEST)

cs-check:
	$(DOCKER) $(CSCHECK)

cs-fix:
	$(DOCKER) $(CSFIX)

psalm:
	$(DOCKER) $(PSALM)

ci: cs-check psalm test

# inside docker

_install:
	$(INSTALL)

_test:
	$(TEST)

_cs-check:
	$(CSCHECK)

_cs-fix:
	$(CSFIX)

_psalm:
	$(PSALM)

_ci: _cs-check _psalm _test

