const IconFavourites = ({ color = '#ffffff' }) => (
  <svg xmlns="http://www.w3.org/2000/svg" width={22} height={19} fill="none">
    <path
      stroke={color}
      strokeLinecap="round"
      strokeWidth={2}
      d="M1.627 12.447c-.14-.165-.263-.315-.369-.447a22.38 22.38 0 0 1 2.624-2.749C5.814 7.553 8.331 6 11 6c2.67 0 5.186 1.553 7.118 3.251A22.37 22.37 0 0 1 20.742 12a22.374 22.374 0 0 1-2.624 2.749C16.186 16.447 13.669 18 11 18c-2.67 0-5.186-1.553-7.118-3.251a22.385 22.385 0 0 1-2.255-2.302ZM11 2V1M6 3l-.646-1.176M16 3l.645-1.176M2.13 6.064 1 5M19.87 6.064 21 5"
    />
    <circle
      cx={11}
      cy={12}
      r={2}
      stroke={color}
      strokeLinecap="round"
      strokeWidth={2}
    />
  </svg>
);
export default IconFavourites;
