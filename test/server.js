const express = require('express');
const app = express();
const mongoose = require('mongoose');
const Product = require('./models/Products');
const User = require('./models/User');


mongoose.connect('mongodb://localhost:27017/narayana', { useNewUrlParser: true });

// Constants
const PORT = process.env.PORT || 3456;


// Routes
app.get('/', (req, res) => {
    User.find()
        .populate('cart.product')
        .then(users => res.send(users))
        .catch(err => res.send(err));
});

app.get('/add', (req, res) => {
    const user = new User({
        name: 'Narayana',
        cart: [
            {
                product: '638da0543173c698cbd3f94f',
                quantity: 2
            },
            {
                product: '638da048e885aa3cbaa91ee6',
                quantity: 3
            }
        ]
    });

    user.save()
        .then(user => res.send(user))
        .catch(err => res.send(err));
});

app.get('/product', (req, res) => {
    const product = new Product({
        name: 'Mobile',
        price: 10000
    })

    product.save()
        .then(product => res.send(product))
        .catch(err => res.send(err));
})


// Server
app.listen(PORT, () => console.log(`Server running @${PORT}`));