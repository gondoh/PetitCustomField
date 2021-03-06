# プチ・カスタムフィールド プラグイン

PetitCustomField プラグインは、ブログ記事に入力欄を追加できるbaserCMS専用のプラグインです。

* プチ・カスタムフィールド設定管理: 利用するカスタムフィールドをコンテンツ毎（ブログ毎）に管理できます。
* プチ・カスタムフィールド管理: コンテンツ毎のカスタムフィールドの並び順、所属コンテンツの編集ができます。
* プチ・カスタムフィールド編集管理: カスタムフィールド内容を編集できます。


## Installation

1. 圧縮ファイルを解凍後、BASERCMS/app/Plugin/PetitCustomField に配置します。
2. 管理システムのプラグイン管理にアクセスし、表示されている PetitCustomField プラグイン をインストール（有効化）して下さい。
3. プチ・カスタムフィールド設定一覧画面にアクセスし、「新規追加」よりフィールドを追加します。
4. フィールド追加後、ブログ記事の投稿画面にアクセスすると、入力項目が追加されてます。


### Use Sample

利用サンプルは以下のエレメントを参照してください。

* /PetitCustomField/View/Elements/petit_blog_custom_field_block.php


## Uses Config

プチ・ブログカスタムフィールド設定画面では、ブログ別に以下の設定を行う事ができます。

* プチ・カスタムフィールドの利用の有無を選択できます。
* プチ・カスタムフィールドの表示位置を選択できます。


## Thanks ##

- [http://basercms.net](http://basercms.net/)
- [http://wiki.basercms.net/](http://wiki.basercms.net/)
- [http://doc.basercms.net/](http://doc.basercms.net/)
- [http://cakephp.jp](http://cakephp.jp)
- [Cake Development Corporation](http://cakedc.com)
- [DerEuroMark](http://www.dereuromark.de/)


### TODO

* カスタムフィールド複製機能
* フィールド編集画面での重複チェック機能
* 固定ページ対応
* ファイルアップロードフィールド
* GoogleMap用フィールド
* カテゴリ別カスタムフィールド
* フィールド設定の自由作成機能
  * フィールド設定毎に利用コンテンツを自由に選択できる
* フィールドグループ機能
  * フィールド設定をグループとして自由に複数作成できる
* 記事別カスタムフィールド指定機能
* 検索機能
