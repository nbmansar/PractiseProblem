def ascWord(word):
    word_list = list(word) 
    word_count = len(word_list)
    for i in range(word_count):
        for j in range(0, word_count-i-1):
            if word_list[j] > word_list[j+1]:
                word_list[j], word_list[j+1] = word_list[j+1], word_list[j]

    sorted_word = ""
    for char in word_list:
        sorted_word += char

    print(sorted_word)

text = "montyPython"
ascWord(text.lower())

