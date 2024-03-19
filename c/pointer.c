#include<stdio.h>
int fun(int *a,int *b);
int main(){

	int a,b;
	scanf("%d%d",&a,&b);

       fun(&a,&b);

	printf("%d",a);


	return 0;
}
	int fun(int *a,int *b)
	{
	int a = *a+*b;
	return 0;
	}

