SELECT category.id, goods.id, goods.name, goods_variations.name AS fullname, goods_variations.price
FROM goods
LEFT JOIN goods_variations ON good_id = goods.id
LEFT JOIN category_links ON category_links.product_id = goods.id
LEFT JOIN category ON category_links.category_id = category.id
WHERE goods.status =1
AND category.id =10000011
ORDER BY category_links.category_id
LIMIT 0 , 30