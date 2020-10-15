memory.css:
	@sassc style.scss -t expanded > $@

clean:
	@rm -f memory.css
