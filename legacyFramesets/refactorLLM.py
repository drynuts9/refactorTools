import openai
import os

def convert_index_file(input_file="index.html", output_file="modern_index.html"):
    # 1. ファイルを読み込む
    if not os.path.exists(input_file):
        print(f"Error: {input_file} が見つかりません。")
        return

    with open(input_file, "r", encoding="utf-8") as f:
        legacy_html = f.read()

    # 2. OpenAI APIの設定 (Gemma/Ollamaを使う場合は base_url を指定)
    client = openai.OpenAI(
        api_key="YOUR_API_KEY",
        # base_url="http://localhost:11434/v1" # Ollamaで動かす場合はここを有効に
    )
    
    prompt = f"""
    以下のレガシーな HTML (frameset形式) を、現代的な HTML5 + CSS (iframe + Grid/Flexbox) に変換してください。
    
    【条件】
    1. frameset の rows/cols の比率を CSS (grid-template-rows 等) で正確に再現すること。
    2. <frame> タグは <iframe> タグに置き換え、name属性やsrc属性を維持すること。
    3. 画面全体(100vh)を使い切るレイアウトにすること。
    4. 各 iframe の境界線は消すこと。
    5. 解説は不要です。HTMLコードのみを出力してください。

    【入力HTML】
    {legacy_html}
    """

    print(f"--- {input_file} を変換中... ---")
    
    try:
        response = client.chat.completions.create(
            model="gpt-4o", # または "gemma2" など
            messages=[{"role": "user", "content": prompt}]
        )
        
        modern_html = response.choices[0].message.content
        
        # Markdownのコードブロック(```html)が含まれる場合の除去処理
        modern_html = modern_html.replace("```html", "").replace("```", "").strip()

        # 3. 結果を書き出す
        with open(output_file, "w", encoding="utf-8") as f:
            f.write(modern_html)
        
        print(f"成功！ {output_file} に保存されました。")

    except Exception as e:
        print(f"エラーが発生しました: {e}")

# 実行
if __name__ == "__main__":
    convert_index_file()
