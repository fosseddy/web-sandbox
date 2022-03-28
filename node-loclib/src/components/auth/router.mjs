import { Router } from "express";

const router = Router();

router.get("/", (req, res) => {
    res.render("auth/login");
});

router.post("/login", (req, res) => {
    req.headers;
    res.send();
});

router.get("/register", (req, res) => {
    res.render("auth/register");
});

router.post("/register", (req, res) => {
    console.log(req.body);
    res.send();
});


export { router };
