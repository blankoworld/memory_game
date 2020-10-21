sources/memory.css:
	@sassc sources/style.scss -t expanded > $@

clean:
	@rm -f sources/memory.css

check:
	@./vendor/bin/phpcs sources/
