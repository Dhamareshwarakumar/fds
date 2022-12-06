const mongoose = require('mongoose');
const Schema = mongoose.Schema;


const UserSchema = new Schema({
    name: String,
    cart: [{
        product: {
            type: Schema.Types.ObjectId,
            ref: 'product'
        },
        quantity: Number
    }]
});

const User = mongoose.model('user', UserSchema);

module.exports = User;