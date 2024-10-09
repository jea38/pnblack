const sliderMainImage = document.getElementById("product-main-image"); // Product container image
const sliderImageList = document.getElementsByClassName("image-list"); // Image thumbnail group selection

for (let i = 0; i < sliderImageList.length; i++) {
  sliderImageList[i].onclick = function () {
    sliderMainImage.src = sliderImageList[i].src;
    console.log(sliderMainImage.src);
  };
}
