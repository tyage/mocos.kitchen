#Yet Another MOCO's Kitchen API

request:  
- recipes.php?id=$id1,$id2 (recipes of $id1, $id2)
- recipes.php?count=10 (last 10 recipes)
- recipes.php (last 20 recipes)

output:
```
[
  {
    "id": /* id of recipe */,
    "title": /* title of recipe */,
    "time": /* time of recipe */,
    "image": /* image url of recipe */,
    "items": [
      {
        "name": /* name of item */,
        "quantity": /* quantity of item */
      }
    ],
    "processes": [
      /* processes of recipe */
    ],
    "points": [
      /* points of recipe */
    ]
  }
]
```
