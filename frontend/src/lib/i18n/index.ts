import type { Dictionary, Locale } from "@/lib/types";
import ru from "./dictionaries/ru.json";
import en from "./dictionaries/en.json";

const dictionaries: Record<Locale, Dictionary> = {
  ru: ru as Dictionary,
  en: en as Dictionary,
};

export function getDictionary(locale: Locale): Dictionary {
  return dictionaries[locale];
}

export function getLocalizedField<T>(
  fieldRu: T,
  fieldEn: T,
  locale: Locale
): T {
  return locale === "ru" ? fieldRu : fieldEn;
}

export function formatDate(dateString: string, locale: Locale): string {
  const date = new Date(dateString);
  return date.toLocaleDateString(locale === "ru" ? "ru-RU" : "en-US", {
    year: "numeric",
    month: "long",
  });
}

export const locales: Locale[] = ["ru", "en"];
export const defaultLocale: Locale = "ru";