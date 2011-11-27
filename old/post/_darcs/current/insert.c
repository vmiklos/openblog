#include <mysql.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "util.h"

#define HOST "localhost"
#define USERNAME "root"
#define PASSWD "foo"
#define DBNAME "openblog"

#define LINELEN 4096

static MYSQL demo_db, *sock;

int main()
{
	int userid;
	int allgood=0;
	char sline[LINELEN], line[LINELEN];
	MYSQL_RES *res;
	MYSQL_ROW row;
	char email[255];
	char title[255];
	char content[4096]; //FIXME
	char query[4096]; // too

	// extract email addr
	while(!feof(stdin))
	{
		fgets(line, LINELEN, stdin);
		substr(sline, line, 0, 6);
		if(!strcmp(sline, "From: "))
		{
			emailtrim(line, email);
			if (allgood==1)
				break;
			else
				allgood=1;
		}
		substr(sline, line, 0, 8);
		if(!strcmp(sline, "Subject:"))
		{
			substr(title, line, 8, strlen(line)-8);
			if(allgood==1)
				break;
			else
				allgood=1;
		}
	}

	//skip headers
	while(!feof(stdin))
	{
		fgets(line, LINELEN, stdin);
		if(!strcmp(line, "\n"))
			break;
	}
	while(!feof(stdin))
	{
		fgets(line, LINELEN, stdin);
		if(strcmp(sline, line))
			strncat(content, line, 4096);
		strcpy(sline, line);
	}

	if(strlen(email) > 254 || strlen(title) > 254 || strlen(content) > 4094)
	{
		fprintf(stderr, "one or more fields are too long");
		return(1);
	}

	printf("mail: %s\n", email);
	printf("cim: %s\n", title);
	printf("stuff: %s\n", content);
	//return(0);

	if(!(sock = mysql_real_connect(&demo_db, HOST, USERNAME, PASSWD, DBNAME, 0, MYSQL_UNIX_ADDR,0)))
	{
		printf("Connecting failed: %s\n", mysql_error(&demo_db));
		return(1);
	}

	sprintf(query, "SELECT id FROM users WHERE email='%s'", email);
	if(mysql_query(sock, query))
	{
		printf("Query failed: %s\n", mysql_error(&demo_db));
		return(1);
	}

	res=mysql_store_result(&demo_db); /* Download result from server */
	if(!(row=mysql_fetch_row(res))) /* Get a row from the results */
	{
		printf("no such user\n");
		return(1);
	}
	
	userid = atoi(row[0]);
	mysql_free_result(res); /* Release memory used to store results. */
	sprintf(query, "INSERT INTO posts (userid, title, content, modositas, letrehozas) VALUES (%d, '%s', '%s', NOW(), NOW())", userid, addslashes(title), addslashes(content));
	if(mysql_query(sock, query))
	{
		printf("Query failed: %s\n", mysql_error(&demo_db));
		return(1);
	}
	mysql_close(&demo_db);

	return(0);
}
