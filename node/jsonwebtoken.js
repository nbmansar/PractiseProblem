const express = require("express");
const jwt = require("jsonwebtoken");
require('dotenv').config();

const app = express();

app.use(express.json());

const newField = [{
    name: 'Ansar',
    class: '12th'
},
{
    name: 'Sakil',
    class: '13th'
}];

const authenticateToken = (req, res, next) => {
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(" ")[1];
    if (!token) return res.sendStatus(401);
    jwt.verify(token, process.env.ACCESS_TOKEN, (err, user) => {
        if (err) return res.sendStatus(403);
        req.user = user;
        next();
    });
};


app.get('/', (req, res) => {
    res.json(newField);
});

app.post('/login', (req, res) => {
    const { username } = req.body;
    const param = { name: username };
    const access_token = jwt.sign(param,process.env.ACCESS_TOKEN);
    console.log({ access_token });

    // Send the access token in the response
    res.json({ access_token });
});

app.get('/posts',authenticateToken,(req,res)=>{
    console.log(req.user.name);
    res.json({})
})

app.listen(4000, () => {
    console.log('Server is running on port 3000');
});
