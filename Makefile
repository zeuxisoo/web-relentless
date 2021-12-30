ICONS_PATH := ./public/assets/icons

all:
	@echo
	@echo "Command                         : Description"
	@echo "------------------------------- : ------------------"
	@echo "make composer                   : Download the composer.phar file"
	@echo "make vendor                     : Install the application vendors"
	@echo "make update-vendor              : Update the application vendors"
	@echo "make server                     : Start the development web server"
	@echo "make assets-hot                 : Run assets server with hot reload"
	@echo "make assets-prod                : Generate production assets"
	@echo "make test                       : Run the phpunit with all test case"
	@echo "------------------------------- : ------------------"
	@echo "make icons                      : Generate the public assets icons for PWA"
	@echo "make git-stats-today            : Show today stats"
	@echo

composer:
	curl https://getcomposer.org/download/latest-stable/composer.phar -o composer.phar

vendor:
	@php composer.phar install

update-vendor:
	@php composer.phar update

server:
	@php artisan serve

assets-hot:
	@yarn run hot

assets-prod:
	@yarn run prod

test:
	@./vendor/bin/phpunit

git-stats-today:
	@git log --numstat --format="" --since=yesterday | awk '{files += 1}{ins += $$1}{del += $$2} END{print "total: "files" files, "ins" insertions(+) "del" deletions(-)"}'

icons:
	for size in 512 192 48 24; do \
		convert -background transparent -density 1200 $(ICONS_PATH)/icon.svg -resize $${size}x $(ICONS_PATH)/icon_$${size}.png; \
	done

	@echo "\nOK!\n"

meilisearch:
	meilisearch --db-path=./storage/default.ms --env=development --http-addr=127.0.0.1:7700 --master-key=masterKey
