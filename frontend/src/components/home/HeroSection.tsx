import Link from "next/link";
import { ArrowDown, Download } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { SocialLinks } from "@/components/shared/SocialLinks";
import type { Locale, Dictionary } from "@/lib/types";

interface HeroSectionProps {
  name: string;
  position: string;
  summary: string;
  githubUrl: string | null;
  linkedinUrl: string | null;
  telegramUrl: string | null;
  email: string | null;
  resumeFile: string | null;
  locale: Locale;
  dict: Dictionary;
}

const focusAreas = [
  "React",
  "TypeScript",
  "Frontend Architecture",
  "E-commerce",
  "Large-scale Applications",
];

export function HeroSection({
  name,
  position,
  summary,
  githubUrl,
  linkedinUrl,
  telegramUrl,
  email,
  resumeFile,
  locale,
  dict,
}: HeroSectionProps) {
  return (
    <section className="relative flex min-h-[calc(100vh-4rem)] flex-col justify-center py-24">
      <div className="flex flex-col items-start gap-8">
        {/* Avatar placeholder */}
        <div className="flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-primary/20 to-primary/10 ring-1 ring-border">
          <span className="text-3xl font-semibold text-primary">
            {name.charAt(0)}
          </span>
        </div>

        {/* Name & Position */}
        <div className="space-y-4">
          <h1 className="text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl">
            {name}
          </h1>
          <p className="text-xl text-muted-foreground sm:text-2xl">
            {position}
          </p>
          <p className="max-w-2xl text-base leading-relaxed text-muted-foreground">
            {summary}
          </p>
        </div>

        {/* Focus Areas */}
        <div className="space-y-3">
          <p className="text-sm font-medium text-muted-foreground">
            {dict.home.focus_areas}
          </p>
          <div className="flex flex-wrap gap-2">
            {focusAreas.map((area) => (
              <Badge key={area} variant="secondary" className="rounded-full px-4 py-1.5 text-sm">
                {area}
              </Badge>
            ))}
          </div>
        </div>

        {/* Actions */}
        <div className="flex flex-wrap items-center gap-4">
          <Link href={`/${locale}/portfolio`}>
            <Button size="lg" className="rounded-full">
              {dict.home.view_portfolio}
              <ArrowDown className="ml-2 h-4 w-4 rotate-45" />
            </Button>
          </Link>

          {resumeFile && (
            <a href={resumeFile} download>
              <Button variant="outline" size="lg" className="rounded-full">
                <Download className="mr-2 h-4 w-4" />
                {dict.home.download_resume}
              </Button>
            </a>
          )}

          <SocialLinks
            github={githubUrl}
            linkedin={linkedinUrl}
            telegram={telegramUrl}
            email={email}
            iconSize={22}
            className="ml-2"
          />
        </div>
      </div>
    </section>
  );
}