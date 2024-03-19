#include<stdio.h>
#include <string.h>
int main(){
char a[100];
fgets(a,sizeof(a),stdin);
for(int i=0;i<strlen(a);i++){
	if(a[i] >= 0 && a[i] <= 9){
	printf("%c",a[i]);
	}
}
}
