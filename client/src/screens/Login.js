import axios from 'axios';
import React, { useEffect, useState } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { useNavigate } from 'react-router-dom';

import { login } from '../features/authSlice';

const Login = () => {
    const navigate = useNavigate();
    const dispatch = useDispatch();

    const isAuthenticated = useSelector(state => state.auth.isAuthenticated);

    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);

    const handleLogin = e => {
        e.preventDefault();
        setLoading(true);

        axios.post('/api/auth/login', { username: email, password })
            .then(res => {
                dispatch(login({
                    token: res.data.data.token,
                }));
            })
            .catch(err => {
                alert(JSON.stringify(err.response.data.error));
            })
            .finally(() => setLoading(false));
    }

    useEffect(() => {
        if (isAuthenticated) {
            navigate('/');
        }
    }, [isAuthenticated, navigate]);

    useEffect(() => {
        document.title = 'Login | Zomato';
    }, []);

    return (
        <div className="container-fluid">
            <section className="row login">
                <div className="col-md-6 imageContainer"></div>
                <div className="col-md-6 px-5 mt-5 loginContainer align-self-md-center">
                    {/* <div className="card"> */}
                    {/* <div className="card-body"> */}
                    <h1 className="text-center text-primary">Login</h1>
                    <form onSubmit={handleLogin}>
                        <div className="form-group">
                            <input
                                type="email"
                                className="form-control"
                                placeholder="Enter email"
                                value={email}
                                onChange={e => setEmail(e.target.value)}
                                disabled={loading}
                            />
                        </div>
                        <div className="form-group">
                            <input
                                type="password"
                                className="form-control"
                                placeholder="Password"
                                value={password}
                                onChange={e => setPassword(e.target.value)}
                                disabled={loading}
                            />
                        </div>
                        <div className="d-grid">
                            <button
                                type="submit"
                                className="btn btn-primary"
                                style={{ backgroundColor: loading ? '#f7746f' : '#E72444' }}
                            >
                                Login
                            </button>
                        </div>
                    </form>
                    {/* </div> */}
                    {/* </div> */}
                </div>
            </section>
        </div>
    );
};

export default Login;