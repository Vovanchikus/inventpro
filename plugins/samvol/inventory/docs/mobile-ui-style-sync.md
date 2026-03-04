# Mobile UI Style Sync Guide

This document defines how to keep React Native UI visually aligned with the current web interface (`themes/invent-pro`).

## 1. Source of truth

- Web tokens: `themes/invent-pro/assets/css/variables.css`
- Web component styles: `themes/invent-pro/assets/css/style.css`
- API docs skin (same visual language): `plugins/samvol/inventory/docs/swagger-ui.html`

## 2. Core tokens to mirror in React Native

Use these tokens in your RN `theme/tokens.ts` and do not hardcode colors in screens.

```ts
export const tokens = {
  color: {
    blue500: '#5B86CA',
    green500: '#48A53B',
    green600: '#3D8B32',
    red500: '#E93850',
    neutral100: '#FDFDFD',
    neutral200: '#F1F1F1',
    neutral300: '#EBEBEB',
    neutral500: '#838383',
    neutral600: '#727272',
    neutral800: '#101010',
  },
  text: {
    primary: '#101010',
    secondary: '#838383',
    muted: '#727272',
    inverse: '#FDFDFD',
  },
  radius: {
    sm: 8,
    md: 12,
    nm: 16,
    lg: 28,
    full: 999,
  },
  spacing: {
    xs: 6,
    sm: 8,
    md: 12,
    lg: 16,
    xl: 24,
  },
};
```

## 3. Button parity (web -> RN)

Web button classes are based on:

- `.button.button--nm.button--brand`
- `.button.button--nm.button--success`
- `.button.button--nm.button--secondary`
- `.button.button--nm.button--error`

Equivalent RN style recipe:

```ts
import { StyleSheet } from 'react-native';
import { tokens } from './tokens';

export const buttonStyles = StyleSheet.create({
  base: {
    minHeight: 44,
    paddingHorizontal: 24,
    paddingVertical: 14,
    borderRadius: tokens.radius.full,
    alignItems: 'center',
    justifyContent: 'center',
  },
  label: {
    fontSize: 14,
    lineHeight: 16,
    fontWeight: '500',
  },
  brand: {
    backgroundColor: '#2A2A2A',
  },
  success: {
    backgroundColor: tokens.color.green500,
    shadowColor: '#3D8B32',
    shadowOpacity: 0.25,
    shadowRadius: 8,
    shadowOffset: { width: 0, height: 4 },
    elevation: 3,
  },
  secondary: {
    backgroundColor: '#E0E0E0',
    borderWidth: 1,
    borderColor: '#CBCBCB',
  },
  error: {
    backgroundColor: tokens.color.red500,
  },
  labelLight: {
    color: tokens.text.inverse,
  },
  labelDark: {
    color: tokens.text.primary,
  },
});
```

## 4. Inputs and cards

- Input radius: `8`
- Input border: `#D6D6D6`
- Focus border: `#727272`
- Card background: `#FDFDFD`
- Card shadow: low blur, subtle elevation only

## 5. Typography alignment

Web uses `Gilroy-Code`. In RN:

- Primary fallback: `Inter` or `SF Pro` / `Roboto` based on platform.
- Keep the same scale:
  - Caption: `12`
  - Body: `14`
  - Section title: `16`
  - Screen title: `24`

## 6. Interaction patterns

- Buttons: short press feedback (opacity/scale 0.98)
- Lists: compact cards with rounded edges
- Modals: blurred or dimmed backdrop and rounded container (`radius.lg`)
- Online/offline badge colors:
  - Online: green family (`green500`)
  - Offline: red family (`red500`)

## 7. Practical workflow

1. Update web tokens in `variables.css`.
2. Mirror token change in RN `tokens.ts`.
3. Validate in 3 critical screens: Login, Product List, Operation Form.
4. Keep Swagger docs skin updated so backend docs remain in same visual language.
