#include <string.h>
#include <ctype.h>
#include "util.h"

int substr(char *to, char *from, int start, int length)
{
	unsigned int i=0;
	unsigned int enabled=0;
	length += start;
	for(;*from!='\0'; from++, i++)
	{
		if(i==start)
			enabled=1;
		if(i==length)
			enabled=0;
		if(enabled==1)
		{
			*to=*from;
			to++;
		}
	}
	*to='\0';
	return(0);
}

// input: 'From: Foo <user@server.com>', output: 'user@server.com'
char* emailtrim(char *from, char *to)
{
	int junk=1;
	if(!strstr(from, "<"))
		substr(to, from, 6, strlen(from)-7);
	else
	{
		for(;*from!='\0';from++)
		{
			if(*from==62)
				junk=1;
			if(junk==0)
			{
				*to=*from;
				to++;
			}
			if(*from==60)
				junk=0;
		}
		*to='\0';
	}
	return(to);
}

// trim whitespace and newlines from a string
char* trim(char *str)
{
	char *pch = str;

	if(*str == '\0')
		// string is empty, so we're done
		return(str);

	while(isspace(*pch)) {
		pch++;
	}
	if(pch != str) {
		memmove(str, pch, (strlen(pch) + 1));
	}

	// check if there wasn't anything but whitespace in the string
	if(*str == '\0') {
		return(str);
	}
	
	pch = (char*)(str + (strlen(str) - 1));
	while(isspace(*pch)) {
		pch--;
	}
	*++pch = '\0';

	return(str);
}

/* this function is ugly -- it malloc's for each string it
   returns, and they probably won't be freed by the caller. */
char* addslashes(const char *s) {
	char slashed[8192];
	char *p;
 
	slashed[0] = '\0';
	p = slashed;
	while(*s) {
		if(*s == '\'' || *s == '"' || *s == '\\') {
			*p++ = '\\';
		}
		*p++ = *s++;
	}
	*p = '\0';
	return(strdup(slashed));
}
