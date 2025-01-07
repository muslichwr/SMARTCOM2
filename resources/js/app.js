import "./bootstrap";
import Alpine from "alpinejs";
import Editor from "@toast-ui/editor";
// import 'codemirror/lib/codemirror.css';
import "@toast-ui/editor/dist/toastui-editor.css";
import "flowbite";
import "@fontsource-variable/figtree";

const editor = new Editor({
    el: document.querySelector("#editor"),
    height: "400px",
    initialEditType: "markdown",
    placeholder: "Write something cool!",
});

window.Alpine = Alpine;

Alpine.start();
