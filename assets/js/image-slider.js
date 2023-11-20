document.addEventListener("DOMContentLoaded", function() {
    // For news silder
    const initNewsSlider = () => {
        const newsWrapper = document.querySelector(".slide-2.news>main>.wrapper");
        const newsCarousel = document.querySelector(".slide-2.news>main>.wrapper>.carousel");
        const newsfirstCardWidth = newsCarousel.querySelector(".slide-2.news>main>.wrapper>.carousel>.card")?.offsetWidth;
        const newsarrowBtns = document.querySelectorAll(".slide-2.news>main>.wrapper>i");
        const newsCarouselChildrens = [...newsCarousel.children];

        let newsIsDragging = false, newsIsAutoPlay = true, newsStartX, newsStartScrollLeft, newsTimeoutId;
        
        // Get the number of cards that can fit in the carousel at once
        let newsCardPerView = Math.round(newsCarousel.offsetWidth / newsfirstCardWidth);

        // Insert copies of the last few cards to beginning of carousel for infinite scrolling
        newsCarouselChildrens.slice(-newsCardPerView).reverse().forEach(card => {
            newsCarousel.insertAdjacentHTML("afterbegin", card.outerHTML);
        });

        // Scroll the carousel at appropriate postition to hide first few duplicate cards on Firefox
        newsCarousel.classList.add("no-transition");
        newsCarousel.scrollLeft = newsCarousel.offsetWidth;
        newsCarousel.classList.remove("no-transition");

        // Add event listeners for the arrow buttons to scroll the carousel left and right
        newsarrowBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                newsCarousel.scrollLeft += btn.id == "news-left" ? -newsfirstCardWidth : newsfirstCardWidth;
            });
        });

        const newsDragStart = (e) => {
            newsIsDragging = true;
            newsCarousel.classList.add("newsDragging");
            // Records the initial cursor and scroll position of the carousel
            newsStartX = e.pageX;
            newsStartScrollLeft = newsCarousel.scrollLeft;
        }
        
        const newsDragging = (e) => {
            if(!newsIsDragging) return; // if newsIsDragging is false return from here
            // Updates the scroll position of the carousel based on the cursor movement
            newsCarousel.scrollLeft = newsStartScrollLeft - (e.pageX - newsStartX);
        }
        
        const newsDragStop = () => {
            newsIsDragging = false;
            newsCarousel.classList.remove("newsDragging");
        }
        
        const newsInfiniteScroll = () => {
            // If the carousel is at the beginning, scroll to the end
            if(newsCarousel.scrollLeft === 0) {
                newsCarousel.classList.add("no-transition");
                newsCarousel.scrollLeft = newsCarousel.scrollWidth - (2 * newsCarousel.offsetWidth);
                newsCarousel.classList.remove("no-transition");
            }
            // If the carousel is at the end, scroll to the beginning
            else if(Math.ceil(newsCarousel.scrollLeft) === newsCarousel.scrollWidth - newsCarousel.offsetWidth) {
                newsCarousel.classList.add("no-transition");
                newsCarousel.scrollLeft = newsCarousel.offsetWidth;
                newsCarousel.classList.remove("no-transition");
            }
        
            // Clear existing timeout & start newsAutoPlay if mouse is not hovering over carousel
            clearTimeout(newsTimeoutId);
            if(!newsWrapper.matches(":hover")) {
                newsAutoPlay();
            } 
        }
        
        const newsAutoPlay = () => {
            if(window.innerWidth < 800 || !newsIsAutoPlay) return; // Return if window is smaller than 800 or newsIsAutoPlay is false
            // newsAutoPlay the carousel after every 2500 ms
            newsTimeoutId = setTimeout(() => newsCarousel.scrollLeft += newsfirstCardWidth, 2500);
        }
        newsAutoPlay();
        
        newsCarousel.addEventListener("mousedown", newsDragStart);
        newsCarousel.addEventListener("mousemove", newsDragging);
        document.addEventListener("mouseup", newsDragStop);
        newsCarousel.addEventListener("scroll", newsInfiniteScroll);
        newsWrapper.addEventListener("mouseenter", () => clearTimeout(newsTimeoutId));
        newsWrapper.addEventListener("mouseleave", newsAutoPlay);
    }
    initNewsSlider();

    //For facilities slider
    const initFacilitiesSlider =() => {
        const carousel = document.querySelector(".slide-2>main>.img-wrapper>.img-carousel"),
        firstImg = carousel.querySelectorAll(".slide-2>main>.img-wrapper>.img-carousel>img")[0],
        arrowIcons = document.querySelectorAll(".slide-2>main>.img-wrapper>i");

        let isDragStart = false, isDragging = false, prevPageX, prevScrollLeft, positionDiff;

        const showHideIcons = () => {
            // showing and hiding prev/next icon according to carousel scroll left value
            let scrollWidth = carousel.scrollWidth - carousel.clientWidth; // getting max scrollable width
            arrowIcons[0].style.display = carousel.scrollLeft == 0 ? "none" : "block";
            arrowIcons[1].style.display = carousel.scrollLeft == scrollWidth ? "none" : "block";
        }

        arrowIcons.forEach(icon => {
            icon.addEventListener("click", () => {
                let firstImgWidth = firstImg.clientWidth + 14; // getting first img width & adding 14 margin value
                // if clicked icon is left, reduce width value from the carousel scroll left else add to it
                carousel.scrollLeft += icon.id == "facilities-left" ? -firstImgWidth : firstImgWidth;
                setTimeout(() => showHideIcons(), 60); // calling showHideIcons after 60ms
            });
        });

        const autoSlide = () => {
            // if there is no image left to scroll then return from here
            if(carousel.scrollLeft - (carousel.scrollWidth - carousel.clientWidth) > -1 || carousel.scrollLeft <= 0) return;

            positionDiff = Math.abs(positionDiff); // making positionDiff value to positive
            let firstImgWidth = firstImg.clientWidth + 14;
            // getting difference value that needs to add or reduce from carousel left to take middle img center
            let valDifference = firstImgWidth - positionDiff;

            if(carousel.scrollLeft > prevScrollLeft) { // if user is scrolling to the right
                return carousel.scrollLeft += positionDiff > firstImgWidth / 3 ? valDifference : -positionDiff;
            }
            // if user is scrolling to the left
            carousel.scrollLeft -= positionDiff > firstImgWidth / 3 ? valDifference : -positionDiff;
        }

        const dragStart = (e) => {
            // updatating global variables value on mouse down event
            isDragStart = true;
            prevPageX = e.pageX || e.touches[0].pageX;
            prevScrollLeft = carousel.scrollLeft;
        }

        const dragging = (e) => {
            // scrolling images/carousel to left according to mouse pointer
            if(!isDragStart) return;
            e.preventDefault();
            isDragging = true;
            carousel.classList.add("dragging");
            positionDiff = (e.pageX || e.touches[0].pageX) - prevPageX;
            carousel.scrollLeft = prevScrollLeft - positionDiff;
            showHideIcons();
        }

        const dragStop = () => {
            isDragStart = false;
            carousel.classList.remove("dragging");

            if(!isDragging) return;
            isDragging = false;
            autoSlide();
        }

        carousel.addEventListener("mousedown", dragStart);
        carousel.addEventListener("touchstart", dragStart);

        document.addEventListener("mousemove", dragging);
        carousel.addEventListener("touchmove", dragging);

        document.addEventListener("mouseup", dragStop);
        carousel.addEventListener("touchend", dragStop);
    }
    initFacilitiesSlider();
});