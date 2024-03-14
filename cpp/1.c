#include <stdio.h>
#include <string.h>

int main()
{
    char a[1000] = "a Hello this is zaaaa";
    int count=0;int space=0; char b[1000];
    int len = strlen(a);
    
    for(int i=0;i<len;i++){
        if(a[i]==' '){
            b[count++] = -1;
        }
    }
    
    for(int j=len;j<0;j--){
        if(b[j] != -1){
            
        }else{
            b[j] = ' ';
        }
    }
    
    for(int j=0;j<=len;j++){
        printf("%c",b[j]);
    }
}
