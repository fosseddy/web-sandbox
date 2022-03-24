const assert = require("assert/strict");
const router = require("express").Router();

let id = 1;
let genres = [
    { id: id++, name: "Poetry" },
    { id: id++, name: "Science fiction" },
    { id: id++, name: "Classic" }
];

function findGenre(req, res, next) {
    assert(req.params.id, "middleware used on route with no :id param?");

    const genre = genres.find(g => g.id == req.params.id);

    if (!genre) {
        throw new Error(`Genre with id ${req.params.id} not found`);
    }

    req.genre = genre;
    next();
}

router.get("/", (req, res) => {
    res.render("genre/index", { genres });
});

router.route("/create")
    .get((req, res) => {
        res.render("genre/create");
    })
    .post((req, res) => {
        const { name } = req.body;
        let errors = {};

        if (!name || !name.trim()) {
            errors.name = "Name is required";
        } else if (name.length < 2 || name.length > 255) {
            errors.name = "Name must be between 2 and 255 characters long";
        }

        if (Object.keys(errors).length) {
            return res.render("genre/create", {
                form: { name, errors }
            });
        }

        genres.push({ id: id++, name });

        res.redirect("/catalog/genre");
    });

router.get("/:id(\\d+)", findGenre, (req, res) => {
    res.render("genre/show", { genre: req.genre });
});

router.route("/:id(\\d+)/update")
    .get((req, res) => {
        return res.json(req.genre);
        res.render("genre/update");
    })
    .post((req, res) => {
        res.redirect(`/catalog/genre/${req.params.id}`);
    });

router.get("/:id(\\d+)/delete", (req, res) => {
    return res.json(req.genre);
    res.send("genre delete " + req.params.id);
    //res.render("genre/delete");
});

module.exports = router;
