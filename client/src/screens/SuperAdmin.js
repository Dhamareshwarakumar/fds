import axios from 'axios';
import React, { useEffect, useState } from 'react';

const SuperAdmin = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [users, setUsers] = useState([]);

    const roles = {
        0: 'User',
        1: 'Restaurant Admin',
        4: 'Super Admin'
    }

    const addRestaurantUser = e => {
        e.preventDefault();

        axios.post('/api/auth/addrestaurantuser', {
            username: email,
            password
        })
            .then(res => {
                setEmail('');
                setPassword('');
                alert(res.data.message);
            })
            .catch(err => {
                alert(JSON.stringify(err.response.data.error));
            });
    }

    useEffect(() => {
        axios.get('/api/auth/users')
            .then(res => {
                console.log(res.data)
                setUsers(res.data);
            })
            .catch(err => {
                alert(JSON.stringify(err.response.data.error));
            });
    }, []);

    return (
        <section>
            <div className="container-fluid mt-2 px-5">
                <div className="row">
                    <div className="col-md-4">
                        <div className="card">
                            <div className="card-body">
                                <p className='my-3 text-center display-6'>Add Restaurant User</p>
                                <form onSubmit={addRestaurantUser}>
                                    <div className="form-group">
                                        <input
                                            type="email"
                                            className="form-control"
                                            name="username"
                                            placeholder='Username'
                                            value={email}
                                            onChange={e => setEmail(e.target.value)}
                                        />
                                    </div>
                                    <div className="form-group">
                                        <input
                                            type="password"
                                            className="form-control"
                                            name="password"
                                            placeholder='Password'
                                            value={password}
                                            onChange={e => setPassword(e.target.value)}
                                        />
                                    </div>
                                    <div className="form-group d-grid">
                                        <button className="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-body">
                                <p className='my-3 text-center display-6'>Users</p>
                                {users.length ? (
                                    <table className="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Username</th>
                                                <th scope="col">Role</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {users.map(user => (
                                                <tr key={user.user_id}>
                                                    <td>{user.username}</td>
                                                    <td>{roles[user.role]}</td>
                                                    <td>
                                                        <button className="btn btn-danger">Delete</button>
                                                    </td>
                                                </tr>

                                            ))}
                                        </tbody>
                                    </table>
                                ) : (<p className='text-center'>No users found</p>)}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default SuperAdmin;