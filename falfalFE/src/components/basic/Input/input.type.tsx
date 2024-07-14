import { InputHTMLAttributes } from 'react';

type InputProps = Omit<InputHTMLAttributes<HTMLInputElement>, 'type'> & {
  type?: 'text' | 'password' | 'number' | 'email' | 'date';
  label?: string;
};

export default InputProps;
