/* Remove unbalanced parentheses in a given expression.

    Eg.) Input  : ((abc)((de))
         Output : ((abc)(de)) 
 
         Input  : (a(b)))(cd)  
         Output : (a(b))(cd)

	 Input  : (a(b)))(c(d)
         Output : (a(b))(cd)

         Input  : (ab))(c(d))))
         Output : (ab)(c(d))

         Input  : (((ab)
         Output : (ab) 

*/
#include<stdio.h>
#include<string.h>
int main()
{
	char str[100];
	int i,j,begin=0,end=0;
	printf("enter the string : ");
	scanf("%s",str);
	int len = strlen(str);
	for(i=0,j=len-1;i<=len;i++,j--){
		if(str[i] == '('){
			 begin++;
		}else if(str[i] == ')'){
			begin--;
		}

		if(str[j] == ')'){
			end++;
		}else if(str[j] == '('){
			end--;
		}

		if(begin < 0){
			str[i]=-1;
		}
		if(end < 0){
			str[j]=-1;
		}	
	}

	for(int k=0;str[k];k++){
		if(str[k] != -1)
			printf("%c",str[k]);
	}
	return 0;
}
