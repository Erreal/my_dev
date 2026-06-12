import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: {
    default: "Mikhail Zakharov — Senior Frontend Developer",
    template: "%s | Mikhail Zakharov",
  },
  description:
    "Senior Frontend Developer with 15+ years of commercial experience. Specializing in React, TypeScript, and Frontend Architecture.",
  metadataBase: new URL("https://erreality.ru"),
  alternates: {
    languages: {
      "ru": "/ru",
      "en": "/en",
    },
  },
  openGraph: {
    type: "website",
    locale: "ru_RU",
    siteName: "Mikhail Zakharov",
    title: "Mikhail Zakharov — Senior Frontend Developer",
    description:
      "Senior Frontend Developer with 15+ years of commercial experience. Specializing in React, TypeScript, and Frontend Architecture.",
    url: "https://erreality.ru",
  },
  robots: {
    index: true,
    follow: true,
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html
      lang="ru"
      className="h-full antialiased"
      suppressHydrationWarning
    >
      <head>
        <script
          dangerouslySetInnerHTML={{
            __html: `
              (function() {
                try {
                  var theme = localStorage.getItem('theme');
                  if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                  }
                } catch(e) {}
              })();
            `,
          }}
        />
      </head>
      <body className="min-h-full font-sans">{children}</body>
    </html>
  );
}
