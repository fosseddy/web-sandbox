const router = require("express").Router();

router.get("/", (req, res) => {
    res.render("catalog/index");
});

module.exports = router;
