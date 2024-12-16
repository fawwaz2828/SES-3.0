import requests
from bs4 import BeautifulSoup

# URL halaman produk Philips Hue (misalnya outdoor lighting)
url = "https://www.philips-hue.com/en-us/products/all-products?filters=FK_HUE_LIGHTING_OUTDOOR_LIGHTING"

# Kirim request untuk mengakses halaman
response = requests.get(url)

# Pastikan status kode HTTP 200 (sukses)
if response.status_code == 200:
    soup = BeautifulSoup(response.content, "html.parser")
    
    # Menemukan semua produk dalam halaman
    products = soup.find_all("div", class_="product-card")  # Anda perlu memeriksa elemen HTML yang tepat
    
    # Loop untuk mengekstrak nama dan harga produk
    for product in products:
        # Mengambil nama produk
        name = product.find("h3", class_="product-name")  # Atau gunakan class/element yang tepat
        if name:
            print(f"Product Name: {name.text.strip()}")

        # Mengambil harga produk
        price = product.find("span", class_="price")  # Atau gunakan class/element yang tepat
        if price:
            print(f"Price: {price.text.strip()}")
else:
    print(f"Error: {response.status_code}")
