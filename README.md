#Yet Another MOCO's Kitchen API

## get all recipes
request:
recipes.php

output:
[
  {
    "id": /* id of recipe */,
    "date": /* date of recipe */
  }
]

## get detail of recipe
request:
recipes.php?id=$id1,$id2

output:
[
  {
    "id": /* id of recipe */,
    "title": /* title of recipe */,
    "time": /* time of recipe */,
    "image": /* image url of recipe */,
    "text": /* text of recipe */
  }
]
