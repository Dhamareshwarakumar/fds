import axios from 'axios';
import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';

import ItemsSlider from '../components/ItemsSlider';
import RestaurantItem from '../components/RestaurantItem';

const Home = () => {
    const navigate = useNavigate();
    const [restauarnts, setRestauarnts] = useState([]);

    useEffect(() => {
        document.title = 'Home | Zomato';
        axios.get('/api/restaurants')
            .then(res => setRestauarnts(res.data.data))
            .catch(err => alert(err.response.data.err));
    }, []);

    return (
        <div>
            <h1 className='text-center'><span className="text-primary">Zomato</span> Welcomes You... 🙏</h1>
            <ItemsSlider />
            <div className="container mt-3">
                <div className="row mt-2">
                    {restauarnts.map(restaurant => (
                        <div
                            className="col-md-4"
                            key={restaurant.restaurant_id}
                            onClick={() => navigate(`/restaurant/${restaurant.restaurant_id}`)}
                        >
                            <RestaurantItem restaurant_name={restaurant.restaurant_name} />
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
};

export default Home;