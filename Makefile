memory.css:
	@sassc sources/style.scss -t expanded > $@

clean:
	@rm -f memory.css

check:
	@./vendor/bin/phpcs sources/

run:
	@composer install
	@docker-compose up -d
