**For Lists:**
1. `append()`: Adds an element to the end of the list.
   - Example:
     ```python
     my_list = [1, 2, 3]
     my_list.append(4)
     print(my_list)  # Output: [1, 2, 3, 4]
     ```

2. `insert()`: Inserts an element at a specified position.
   - Example:
     ```python
     my_list = [1, 2, 3]
     my_list.insert(1, 5)  # Insert 5 at index 1
     print(my_list)  # Output: [1, 5, 2, 3]
     ```

3. `remove()`: Removes the first occurrence of a specified element.
   - Example:
     ```python
     my_list = [1, 2, 3, 2]
     my_list.remove(2)  # Removes the first occurrence of 2
     print(my_list)  # Output: [1, 3, 2]
     ```

4. `pop()`: Removes and returns an element at a specified index.
   - Example:
     ```python
     my_list = [1, 2, 3]
     popped_element = my_list.pop(1)  # Remove element at index 1 (2) and store it
     print(my_list)  # Output: [1, 3]
     print(popped_element)  # Output: 2
     ```

5. `sort()`: Sorts the list in ascending order.
   - Example:
     ```python
     my_list = [3, 1, 2]
     my_list.sort()
     print(my_list)  # Output: [1, 2, 3]
     ```

6. `reverse()`: Reverses the order of elements in the list.
   - Example:
     ```python
     my_list = [1, 2, 3]
     my_list.reverse()
     print(my_list)  # Output: [3, 2, 1]
     ```

7. `len()`: Returns the number of elements in the list.
   - Example:
     ```python
     my_list = [1, 2, 3, 4, 5]
     length = len(my_list)
     print(length)  # Output: 5
     ```

**For Strings:**
1. `upper()`: Converts all characters to uppercase.
   - Example:
     ```python
     my_string = "Hello, World!"
     upper_case = my_string.upper()
     print(upper_case)  # Output: "HELLO, WORLD!"
     ```

2. `lower()`: Converts all characters to lowercase.
   - Example:
     ```python
     my_string = "Hello, World!"
     lower_case = my_string.lower()
     print(lower_case)  # Output: "hello, world!"
     ```

3. `strip()`: Removes leading and trailing whitespace.
   - Example:
     ```python
     my_string = "   Hello, World!   "
     stripped = my_string.strip()
     print(stripped)  # Output: "Hello, World!"
     ```

4. `split()`: Splits a string into a list of substrings based on a delimiter.
   - Example:
     ```python
     my_string = "apple,banana,cherry"
     parts = my_string.split(',')
     print(parts)  # Output: ['apple', 'banana', 'cherry']
     ```

5. `join()`: Joins a list of strings into one string using a delimiter.
   - Example:
     ```python
     parts = ['apple', 'banana', 'cherry']
     joined = ', '.join(parts)
     print(joined)  # Output: "apple, banana, cherry"
     ```

6. `find()`: Finds the first occurrence of a substring.
   - Example:
     ```python
     my_string = "Hello, World!"
     position = my_string.find("World")
     print(position)  # Output: 7
     ```

7. `replace()`: Replaces occurrences of a substring with another string.
   - Example:
     ```python
     my_string = "Hello, World!"
     new_string = my_string.replace("Hello", "Hi")
     print(new_string)  # Output: "Hi, World!"
     ```

**For Dictionaries:**
1. `keys()`: Returns a list of keys in the dictionary.
   - Example:
     ```python
     my_dict = {'apple': 1, 'banana': 2, 'cherry': 3}
     keys = my_dict.keys()
     print(keys)  # Output: dict_keys(['apple', 'banana', 'cherry'])
     ```

2. `values()`: Returns a list of values in the dictionary.
   - Example:
     ```python
     my_dict = {'apple': 1, 'banana': 2, 'cherry': 3}
     values = my_dict.values()
     print(values)  # Output: dict_values([1, 2, 3])
     ```

3. `items()`: Returns a list of key-value pairs as tuples.
   - Example:
     ```python
     my_dict = {'apple': 1, 'banana': 2, 'cherry': 3}
     items = my_dict.items()
     print(items)  # Output: dict_items([('apple', 1), ('banana', 2), ('cherry', 3)])
     ```

4. `get()`: Retrieves the value associated with a key, providing a default value if the key is not found.
   - Example:
     ```python
     my_dict = {'apple': 1, 'banana': 2, 'cherry': 3}
     value = my_dict.get('apple', 0)  # If 'apple' exists, return its value; otherwise, return 0
     print(value)  # Output: 1
     ```

5. `update()`: Updates a dictionary with key-value pairs from another dictionary.
   - Example:
     ```python
     dict1 = {'a': 1, 'b': 2}
     dict2 = {'b': 3, 'c': 4}
     dict1.update(dict2)
     print(dict1)  # Output: {'a': 1, 'b': 3, 'c': 4}
     ```

**For Sets:**
1. `add()`: Adds an element to the set.
   - Example:
     ```python
     my_set = {1, 2, 3}
     my_set.add(4)
     print(my_set)  # Output: {1, 2, 3, 4}
     ```

2. `remove()`: Removes a specified element from the set.
   - Example:
     ```python
     my_set = {1, 2, 3}
     my_set.remove(2)
     print(my_set)  # Output: {1, 3}
     ```

3. `union()`: Returns the union of two sets.
   - Example:
     ```python
     set1 = {1, 2, 3}
     set2 = {3, 4, 5}
     union_set = set1.union(set2)
     print(union_set)  # Output: {1, 2, 3, 4, 5}
     ```

4. `intersection()`: Returns the intersection of two sets.
   - Example:
     ```python
     set1 = {1, 2, 3}
     set2 = {3, 4, 5}
     intersection_set = set1.intersection(set2)
     print(intersection_set)  # Output: {3}
     ```

5. `difference()`: Returns the difference between two sets.
   - Example:
     ```python
     set1 = {1, 2, 3}
     set2 = {3, 4, 5}
     difference_set = set1.difference(set2)
     print(difference_set)  # Output: {1, 2}
     ```

