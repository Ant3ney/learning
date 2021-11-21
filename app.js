let app = require('express')();

app.get('/', (req, res) => {
   res.send('test message');
});

app.listen(process.env.PORT || 3000, () => {
   console.log('App is online on port' + (process.env.PORT || 3000));
});
