import { LabelHTMLAttributes } from 'react';

type LabelProps = Omit<LabelHTMLAttributes<HTMLLabelElement>, 'type'> & {
  label?: string;
};

export default LabelProps;
