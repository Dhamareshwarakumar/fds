import React, { useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { useParams } from 'react-router-dom';
import { getRestaurant } from '../features/restaurantSlice';
import { getDining, reserveDining } from '../features/diningSlice';


const Dining = () => {
    const dispatch = useDispatch();
    const { restaurantId } = useParams();
    const restaurant = useSelector(state => state.restaurant);
    const dining = useSelector(state => state.dining).data;

    useEffect(() => {
        dispatch(getRestaurant(restaurantId));
        dispatch(getDining(restaurantId));
    }, [dispatch, restaurantId]);

    useEffect(() => {
        document.title = restaurant.restaurant_name + ' | Zomato';
    }, [restaurant]);

    return (
        <div className="container dining">
            <h1 className="text-center"> Reserve in <span className="text-primary">{restaurant.restaurant_name}</span></h1>
            <div className="row justify-content-between mt-5">
                {dining.map(table => (
                    <div className="col-md-5 tables" key={table.dining_id}>
                        <h2 className="text-center">{table.table_name}</h2>
                        <div className="row justify-content-center align-items-center selector">
                            {Number(table.dining_status) ? (
                                <div className="col-auto">
                                    <button className="btn btn-primary" disabled>Reserved</button>
                                </div>
                            ) : (
                                <div className="col-auto">
                                    <button className="btn btn-success" onClick={() => dispatch(reserveDining(restaurantId, table.dining_id))}>Select</button>
                                </div>
                            )}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default Dining;