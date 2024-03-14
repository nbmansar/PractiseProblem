#include<stdio.h>
#include<string.h>
#include <stdlib.h>

int main(){
	char a[] = "a4b3c2d6";char temp;int wordCount = 1;
	int len = strlen(a);
	for(int i=0;i<len;i++){
		if(i%2 != 0){
			int num = a[i] - '0';
			printf("%d",a[i]);
		}
		printf("\n");
	}
return 0;
}

