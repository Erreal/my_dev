import Link from "next/link";
import { getDictionary } from "@/lib/i18n";
import { Button } from "@/components/ui/button";
import { ArrowLeft } from "lucide-react";

export default function NotFoundPage() {
  // Default to Russian for the not-found page since we can't determine locale
  const dict = getDictionary("ru");

  return (
    <div className="mx-auto max-w-4xl px-6 py-24">
      <div className="flex flex-col items-center justify-center text-center py-24">
        <h1 className="text-8xl font-bold tracking-tight text-muted-foreground/20 mb-6">
          404
        </h1>
        <h2 className="text-2xl font-semibold mb-3">
          {dict.common.not_found}
        </h2>
        <p className="text-muted-foreground mb-8 max-w-md">
          Страница, которую вы ищете, не существует или была перемещена.
        </p>
        <Link href="/ru">
          <Button variant="outline" className="gap-2 rounded-full">
            <ArrowLeft className="h-4 w-4" />
            На главную
          </Button>
        </Link>
      </div>
    </div>
  );
}