import { InputHTMLAttributes } from 'react';

type TextAreaProps = Omit<InputHTMLAttributes<HTMLTextAreaElement>, 'type'> & {
  label?: string;
};

export default TextAreaProps;
