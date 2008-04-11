doc: HEADER.html Changelog

HEADER.html: README
	ln -s README HEADER.txt
	asciidoc -a toc -a numbered -a sectids HEADER.txt
	rm HEADER.txt

Changelog: _darcs/inventory
	darcs changes > Changelog
