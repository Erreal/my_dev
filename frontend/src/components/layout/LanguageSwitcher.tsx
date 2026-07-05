"use client";

import { usePathname } from "next/navigation";
import Link from "next/link";
import { useMemo } from "react";
import type { Locale } from "@/lib/types";
import { locales } from "@/lib/i18n";
import { Globe } from "lucide-react";
import { Button } from "@/components/ui/button";

export function LanguageSwitcher() {
  const pathname = usePathname();

  const currentLocale = useMemo<Locale>(() => {
    const match = pathname.match(/^\/(ru|en)/);
    return (match?.[1] as Locale) || "ru";
  }, [pathname]);

  const otherLocale = currentLocale === "ru" ? "en" : "ru";

  const switchHref = useMemo(() => {
    return pathname.replace(/^\/(ru|en)/, `/${otherLocale}`);
  }, [pathname, otherLocale]);

  return (
    <div className="flex items-center gap-1">
      {locales.map((locale) => {
        const href = pathname.replace(/^\/(ru|en)/, `/${locale}`);
        const isActive = locale === currentLocale;
        return (
          <Link
            key={locale}
            href={href}
            className={`text-sm font-medium px-2 py-1 rounded-md transition-colors ${
              isActive
                ? "text-foreground bg-muted"
                : "text-muted-foreground hover:text-foreground hover:bg-muted/50"
            }`}
          >
            {locale.toUpperCase()}
          </Link>
        );
      })}
    </div>
  );
}