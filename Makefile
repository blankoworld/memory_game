# Feuille de style du projet
memory.css:
	@sassc sources/style.scss -t expanded > $@

# Nettoie les fichiers générés par `memory.css`
clean:
	@rm -f memory.css

# Vérifie les fichiers au regard du PSR12 (entre autre)
check:
	@./vendor/bin/phpcs --standard=PSR12 sources/ index.php
	@./vendor/bin/phpcs memory.js sources/style.scss

# Corrige les fichiers au regard du PSR12 (entre autre)
fix:
	@./vendor/bin/phpcbf --standard=PSR12 sources/ index.php
	@./vendor/bin/phpcbf memory.js sources/style.scss

# `make run` lance des conteneurs Docker contenant ce qu'il faut pour Memory.
run:
	@make clean && make
	@composer install
	@docker-compose up -d
