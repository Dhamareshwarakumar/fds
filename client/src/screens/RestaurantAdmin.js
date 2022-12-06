import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useSelector, useDispatch } from 'react-redux';
import { getRestaurant } from '../features/restaurantSlice';
import { addDish, getDishes, deleteDish } from '../features/dishesSlice';
import { getOrders } from '../features/ordersSlice';

const RestaurantAdmin = () => {
    const dispatch = useDispatch();
    const userId = useSelector(state => state.auth.user_id);
    const dishes = useSelector(state => state.dishes.data);
    const restaurant = useSelector(state => state.restaurant);
    const orders = useSelector(state => state.orders.data);

    const initialRestaurant = {
        restaurant_name: '',
        restaurant_lat: '',
        restaurant_lng: '',
        restaurant_city: '',
        restaurant_contact: ''
    };

    const initialDish = {
        dish_name: '',
        dish_price: '',
        dish_description: '',
        dish_image: '',
        dish_type: ''
    };

    const [restaurantForm, setRestaurantForm] = useState(initialRestaurant);
    const [dishForm, setDishForm] = useState(initialDish);
    const [report, setReport] = useState(null);

    const handleRestaurantFormChange = e => {
        setRestaurantForm({
            ...restaurantForm,
            [e.target.name]: e.target.value
        });
    };

    const handleRestaurantForm = e => {
        e.preventDefault();

        axios.post('/api/restaurants', restaurantForm)
            .then(res => {
                setRestaurantForm(initialRestaurant);
                alert(res.data.data);
            })
            .catch(err => {
                alert(err.response.data.error);
            });
    }

    const handleDishFormChange = e => {
        setDishForm({
            ...dishForm,
            [e.target.name]: e.target.value
        });
    };

    const handleDishForm = e => {
        e.preventDefault();

        dispatch(addDish(dishForm));
        setDishForm(initialDish);
    };

    const handleDeleteDish = dishID => {
        dispatch(deleteDish(dishID));
    }

    const generate_report = () => {
        axios.get('/api/orders/report')
            .then(res => {
                setReport(res.data.data);
            })
            .catch(err => {
                alert(err.response.data.error);
            });
    }

    useEffect(() => {
        dispatch(getRestaurant(userId));
        dispatch(getDishes(userId));
        dispatch(getOrders(userId));
    }, [dispatch, userId]);

    useEffect(() => {
        setRestaurantForm(restaurant);
    }, [restaurant]);

    return (
        <section>
            <div className="container-fluid mt-2 px-5">
                <div className="row">
                    <div className="col-md-4 my-2">
                        <div className="card">
                            <div className="card-body">
                                <form onSubmit={handleRestaurantForm}>
                                    <div className="form-group">
                                        <input
                                            type="text"
                                            className="form-control"
                                            name="restaurant_name"
                                            placeholder='Restaurant Name'
                                            value={restaurantForm.restaurant_name}
                                            onChange={handleRestaurantFormChange}
                                        />
                                    </div>
                                    <div className="form-group">
                                        <input
                                            type="number"
                                            className="form-control"
                                            name="restaurant_lat"
                                            placeholder='Latitude'
                                            value={restaurantForm.restaurant_lat}
                                            onChange={handleRestaurantFormChange}
                                        />
                                    </div>
                                    <div className="form-group">
                                        <input
                                            type="number"
                                            className="form-control"
                                            name="restaurant_lng"
                                            placeholder='Longitude'
                                            value={restaurantForm.restaurant_lng}
                                            onChange={handleRestaurantFormChange}
                                        />
                                    </div>
                                    <div className="form-group">
                                        <input
                                            type="text"
                                            className="form-control"
                                            name="restaurant_city"
                                            placeholder='City'
                                            value={restaurantForm.restaurant_city}
                                            onChange={handleRestaurantFormChange}
                                        />
                                    </div>
                                    <div className="form-group">
                                        <input
                                            type="text"
                                            className="form-control"
                                            name="restaurant_contact"
                                            placeholder='Restaurant Contact'
                                            value={restaurantForm.restaurant_contact}
                                            onChange={handleRestaurantFormChange}
                                        />
                                    </div>
                                    <div className="d-grid">
                                        <button type='submit' className="btn btn-primary">Add Restaurant</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-8 my-2">
                        <div className="card">
                            <div className="card-body">
                                <p className='text-center display-6 text-primary'>Dishes</p>
                                {dishes.length ? (
                                    <table className="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Type</th>
                                                <th scope="col">Image</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {dishes.map(dish => (
                                                <tr key={dish.dish_id}>
                                                    <td>{dish.dish_name}</td>
                                                    <td>{dish.dish_price}</td>
                                                    <td>{dish.dish_description}</td>
                                                    <td>{dish.dish_type}</td>
                                                    <td><img src={dish.dish_image} width={80} alt={dish.dish_name} /></td>
                                                    <td>
                                                        <button className="btn btn-danger" onClick={() => handleDeleteDish(dish.dish_id)}>Delete</button>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                ) : (
                                    <p className='text-center'>No Dishes</p>
                                )}
                            </div>
                        </div>
                    </div>
                    <div className="col-md-4 my-2">
                        <div className="card">
                            <div className="card-body">
                                <form onSubmit={handleDishForm}>
                                    <div className="form-group">
                                        <input
                                            type="text"
                                            className="form-control"
                                            name="dish_name"
                                            placeholder='Dish Name'
                                            value={dishForm.dish_name}
                                            onChange={handleDishFormChange}
                                        />
                                    </div>
                                    <div className="form-group">
                                        <input
                                            type="number"
                                            className="form-control"
                                            name="dish_price"
                                            placeholder='Price'
                                            value={dishForm.dish_price}
                                            onChange={handleDishFormChange}
                                        />
                                    </div>
                                    <div className="form-group">
                                        <input
                                            type="text"
                                            className="form-control"
                                            name="dish_description"
                                            placeholder='Description'
                                            value={dishForm.dish_description}
                                            onChange={handleDishFormChange}
                                        />
                                    </div>
                                    <div className="form-group">
                                        <input
                                            type="text"
                                            className="form-control"
                                            name="dish_type"
                                            placeholder='Type'
                                            value={dishForm.dish_type}
                                            onChange={handleDishFormChange}
                                        />
                                    </div>
                                    <div className="form-group">
                                        <input
                                            type="text"
                                            className="form-control"
                                            name="dish_image"
                                            placeholder='Image'
                                            value={dishForm.dish_image}
                                            onChange={handleDishFormChange}
                                        />
                                    </div>
                                    <div className="d-grid">
                                        <button type='submit' className="btn btn-primary">Add Dish</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-8 my-2">
                        <div className="card">
                            <div className="card-body">
                                <p className='text-center display-6 text-primary'>Orders</p>
                                {orders.length ? (
                                    <table className="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Username</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {orders.map(order => (
                                                <tr key={order.created_at}>
                                                    <td>{order.username}</td>
                                                    <td>{order.order_status}</td>
                                                    <td>{order.created_at}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                ) : (
                                    <p className='text-center'>No Dishes</p>
                                )}
                                <div>
                                    <button className='btn btn-primary' onClick={generate_report}>Generate Report</button>
                                    {report && (
                                        <div className="col-12 mt-2 border">
                                            <p>Orders: {report.ordersCount}</p>
                                            <p>Amount: {report.totalAmount}</p>
                                            <p>Orders: {report.orders.length}</p>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default RestaurantAdmin;