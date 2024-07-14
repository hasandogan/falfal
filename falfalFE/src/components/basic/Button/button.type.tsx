import { ButtonHTMLAttributes } from 'react';

type ButtonProps = Omit<ButtonHTMLAttributes<HTMLButtonElement>, 'type'> & {
  type?: 'button' | 'submit' | 'reset';
};

export default ButtonProps;
