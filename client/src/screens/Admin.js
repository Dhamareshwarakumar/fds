import React from 'react';
import { useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';

import RestaurantAdmin from './RestaurantAdmin';
import SuperAdmin from './SuperAdmin';

const Admin = () => {
    const navigate = useNavigate();
    const role = useSelector(state => state.auth.role);

    if (role === 1) return <RestaurantAdmin />;
    if (role === 4) return <SuperAdmin />;
    return navigate('/');
}

export default Admin