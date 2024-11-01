import { get } from 'lodash';

export const stateGetter = definition => state => get(state, definition)