/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                "purple-brown": "#46211A",
                "rust": "#A43820",
                "light-apricot": "#F1D3B2",
                "reddish-brown" : "#A43820",
                "light-blue" : "#6C9DDD"
            },
            backgroundSize: {
                "100%": "100%",
                16: "4rem",
                "85%": "85%",
            },
            backgroundImage: {
                "gradient-text":
                    "linear-gradient(to right, #FF0000, #FBFF00, #0090E9)",
            },
            textColor: {
                gradient: "transparent",
            },
            height: {
                90: "90%",
            },
        },
    },
    plugins: [],
};

