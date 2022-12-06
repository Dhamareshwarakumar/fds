import React, { useState } from 'react';

const ItemsSlider = () => {
    const [items] = useState([
        {
            "id": 1,
            "name": "Idli",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/idli.png",
        },
        {
            "id": 2,
            "name": "Dosa",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/dosa.png",
        },
        {
            "id": 3,
            "name": "Vada",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/vada.png",
        },
        {
            "id": 4,
            "name": "Upma",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/upma.png",
        },
        {
            "id": 5,
            "name": "Juice",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/juice.png",
        },
        {
            "id": 6,
            "name": "Pizza",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/pizza.png",
        },
        {
            "id": 7,
            "name": "Burger",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/burger.png",
        },
        {
            "id": 8,
            "name": "Paratha",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/paratha.png",
        },
        {
            "id": 9,
            "name": "Uttapam",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/uttapam.png",
        },
        {
            "id": 10,
            "name": "Ice Cream",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/ice_cream.png",
        },
        {
            "id": 11,
            "name": "Milkshake",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/milkshake.png",
        },
        {
            "id": 12,
            "name": "Chicken",
            "image": "https://raw.githubusercontent.com/Dhamareshwarakumar/slider/main/assets/img/items/chicken.png",
        }
    ]);

    // const [leftHiddenItems, setLeftHiddenItems] = useState(0);
    // const [scrollLength, setScrollLength] = useState(0);
    // const [sliderItems, setSliderItems] = useState([]);

    // const move_right = (scrollLength, items, leftHiddenItems) => {
    //     let currentPosition = leftHiddenItems * -scrollLength;
    //     if (leftHiddenItems > 0) {
    //         setLeftHiddenItems(leftHiddenItems - 1);
    //         console.log('moving right', currentPosition, leftHiddenItems);
    //         for (const i of items) {
    //             i.style.left = currentPosition + scrollLength + 'px';
    //         }
    //     }
    //     return leftHiddenItems;
    // };

    // const move_left = (scrollLength, items, leftHiddenItems) => {
    //     const itemsOnPage = Math.floor(window.innerWidth / items[0].offsetWidth);
    //     let currentPosition = leftHiddenItems * -scrollLength;
    //     if (leftHiddenItems < (items.length - itemsOnPage)) {
    //         setLeftHiddenItems(leftHiddenItems + 1);
    //         console.log('moving left', currentPosition, leftHiddenItems);
    //         for (const i of items) {
    //             i.style.left = currentPosition - scrollLength + 'px';
    //         }
    //     }
    //     return leftHiddenItems;
    // };

    // const reset = (scrollLength, items, leftHiddenItems) => {
    //     const itemsOnPage = Math.floor(window.innerWidth / items[0].offsetWidth)
    //     if (leftHiddenItems > items.length - itemsOnPage) {
    //         leftHiddenItems = items.length - itemsOnPage;
    //         console.log('Weird', leftHiddenItems);
    //     }

    //     let currentPosition = leftHiddenItems * -scrollLength;
    //     for (const i of items) {
    //         i.style.left = currentPosition + 'px';
    //     }
    //     return leftHiddenItems;
    // };

    // const handleLeftArrow = e => {
    //     setLeftHiddenItems(move_right(scrollLength, sliderItems, leftHiddenItems));
    // };

    // const handleRightArrow = e => {
    //     setLeftHiddenItems(move_left(scrollLength, sliderItems, leftHiddenItems));
    // };

    // useEffect(() => {
    //     setSliderItems(document.getElementsByClassName('sliderItem'));
    // }, []);

    // useEffect(() => {
    //     if (sliderItems.length > 0) {
    //         setScrollLength(sliderItems[0].offsetWidth);
    //     }
    // }, [sliderItems, window.innerWidth]);

    return (
        <section className="sliderContainer">
            <div className="container">
                <div>
                    <p className='ms-md-4 display-6'>Inspiration for your first order</p>
                </div>
                <div className="slider">
                    {items.map(item => (
                        <div className="sliderItemContainer" key={item.id}>
                            <div className="sliderItem">
                                <img src={item.image} alt="" />
                            </div>
                        </div>
                    ))}
                </div>
                {/* <div className="sliderLeft">
                <div className="sliderArrow" onClick={handleLeftArrow}>{'<'}</div>
            </div>
            <div className="sliderRight">
                <div className="sliderArrow" onClick={handleRightArrow}>{'>'}</div>
            </div> */}
            </div>
        </section>
    );
};

export default ItemsSlider;