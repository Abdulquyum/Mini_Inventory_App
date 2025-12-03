# Create

curl -X POST http://127.0.0.1:8000/api/products -H "Content-Type: application/json" -d '{"name":"Test","price":9.99,"stock":10}'

# Get

curl http://127.0.0.1:8000/api/products/{id}

# Update

curl -X PUT http://127.0.0.1:8000/api/products/1 -H "Content-Type: application/json" -d '{"price":12.5,"stock":20}'
