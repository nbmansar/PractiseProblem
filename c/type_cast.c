#include<stdio.h>


int main()
{
	char str[] = "123456782";

	long int a;

	a = atol(str);

	printf("%.2lf\n",(double)a/100);

	//printf("%ld",b);

	return 0;

}
