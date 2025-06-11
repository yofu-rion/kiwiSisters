// px単位のspacingを生成
const range = (start, end) =>
  Array.from({ length: end - start + 1 }, (_, i) => start + i);

const generateSpacing = () =>
  range(1, 800).reduce((acc, i) => {
    acc[i] = `${i}px`;
    return acc;
  }, {});

module.exports = {
  content: [
    "./**/*.php",
    "./**/*.html",
    "./js/**/*.js",
  ],
  theme: {
    extend: {
      spacing: {
        px: "1px",
        ...generateSpacing(),
      },
      colors: {
        pink: {
          DEFAULT: "#F777A6",
          dark: "#DA3170",
        },
        blue: {
          verylight: "#FAFBFF",
          light: "#CBD3F2",
          dark: "#3959CC",
        },
        green: {
          DEFAULT: "#50DF5E",
        },
      },
      fontFamily: {
        kiwi: ['"Kiwi Maru"', 'serif'],
        mPlus: ['"M PLUS Rounded 1c"', 'serif'],
      },
      borderWidth: {
        DEFAULT: "1px",
        "0": "0",
        "2": "2px",
        "3": "3px",
        "4": "4px",
        "6": "6px",
        "8": "8px",
      },
    },
  },
  plugins: [],
};
