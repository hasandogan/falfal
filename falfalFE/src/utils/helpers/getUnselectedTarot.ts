import { ITarot } from '../interfaces/ITarot';

export const getUnselectedTarot = (
  selectableIds: ITarot[],
  selectedIds: ITarot[]
): ITarot => {
  const selectedIdSet = new Set(selectedIds.map((tarot) => tarot.id));

  const unselectedTarots = selectableIds.filter(
    (tarot) => !selectedIdSet.has(tarot.id)
  );

  const randomIndex = Math.floor(Math.random() * unselectedTarots.length);
  return unselectedTarots[randomIndex];
};
